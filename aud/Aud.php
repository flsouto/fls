<?php

if(!is_dir(__DIR__.'/tmp_dir/')){
    mkdir(__DIR__.'/tmp_dir/');
}

if(!is_dir(__DIR__.'/meta/')){
    mkdir(__DIR__.'/meta/');
}

class Aud{

	var $file;
	protected $auto_gc = 60 * 5;

	function __construct($input, $reference=false){

        $id = uniqid();

        if($input instanceof self){
			$input = $input->file;
		}

		if(substr($input, 0, 7)=='silence'){
            $len = substr($input, 7);
            $this->file = __DIR__.'/tmp_dir/silence'.$id.'.wav';
		    shell_exec("sox -n -r 44100 -c 2 '$this->file' trim 0 $len");
        } else {

		    if($reference){
		        $this->file = $input;
            } else {
                $ext = explode('.',$input);
                $ext = end($ext);
                $this->file = __DIR__.'/tmp_dir/smp'.$id.'.'.$ext;
                copy($input, $this->file);
            }
        }


	}

    function hash(){
        return md5(file_get_contents($this->file));
    }

	static function silence($length=0){
	    return new self("silence $length");
    }

    static function join(array $samples){
        $files = [];
        foreach($samples as $s){
            if($s instanceof self){
                $files[] = $s->file;
            } else {
                $files[] = $s;
            }
        }
        $id = uniqid();
        $newf = "/tmp/$id.wav";
        exec("sox ".implode(' ',$files)." $newf");
        return new self($newf, true);
    }

    static function select($path, $reference=false){
        if(is_array($path)){
            shuffle($path);
            $path = current($path);
        }
        $files = glob($path);
        shuffle($files);
        $path = current($files);
        return new self($path, $reference);
    }

	function __invoke(){
		return new self($this);
	}

    function meta($key, $value='__GET__'){
        $meta = [];
        $hash = $this->hash();
        if(file_exists($f=__DIR__."/meta/".$hash.".json")){
            $meta = json_decode(file_get_contents($f),true);
        }
        if($value == '__GET__'){
            return isset($meta[$value]) ? $meta[$value] : null;
        }
        $meta[$key] = $value;
        file_put_contents($f, json_encode($meta, JSON_PRETTY_PRINT));
        return $this;
    }

	function len(){
		$len = trim(`soxi -d '{$this->file}'`);
		$parts = explode(":",$len);
		$len = 0;
		if(!empty($parts[0])){
            $len = intval($parts[0]) * 3600;
        }
        if(!empty($parts[1])){
			$len += intval($parts[1]) * 60;
		}
		if(isset($parts[2])){
			$len += ltrim($parts[2],'0');
		}
		return $len;

	}
	
	function amp(){
    	$amp = exec("sox '{$this->file}' -n stat 2>&1 | grep 'Maximum amp'");
        $amp = explode(':',$amp);
        $amp = trim($amp[1]);
        return $amp;
	}


    function getpos($expr){

        $expr = "$expr";
        $invert = false;

        if(strstr($expr,'-')){
            $invert = true;
            $expr = str_replace('-','',$expr);
        }

        if(strstr($expr,'/')){
            $parts = explode('/',$expr);
            $pos = $this->len() * $parts[0] / $parts[1];
        } else {
            $pos = $expr;
        }

        return $invert ? $this->len()-$pos : $pos;

    }

    function range($from ,$to=null){

        $range = [];

        $range[0] = $this->getpos($from);
        if(!$to){
            $to = $this->len()-$range[0];
        } else {
            $to = $this->getpos($to);
        }

        $range[1] = $to;

        return $range;

    }

    function stereo(){
        $out = __DIR__.'/tmp_dir/stereo'.uniqid().'.wav';
        shell_exec("sox -M -c 1 '{$this->file}' -c 1 '{$this->file}' '$out'");
        copy($out, $this->file);
        return $this;
    }

	function mod($filters){
		$out = __DIR__.'/tmp_dir/mod'.uniqid().'.wav';
		shell_exec("sox '{$this->file}' '$out' $filters");
		copy($out, $this->file);
		return $this;
	}

	function cut($offset, $length=null){

	    list($offset,$length) = $this->range($offset,$length);

		return $this->mod("trim $offset $length");
	}

	function copy($offset, $length=null){
	    $id = uniqid();
	    $out = __DIR__."/tmp_dir/cpy{$id}.wav";
        list($offset,$length) = $this->range($offset,$length);
	    shell_exec("sox '{$this->file}' '$out' trim $offset $length");
	    return new self($out, true);
    }

	function add($input){
		if($input instanceof self){
			$input = $input->file;
		}
		$out = __DIR__.'/tmp_dir/mod'.uniqid().'.wav';
		shell_exec("sox '{$this->file}' '$input' '$out'");
		copy($out, $this->file);
		return $this;
	}
	
	function mix($input, $normalize=true){
		if($input instanceof self){
			$input = $input->file;
		}
		$out = __DIR__.'/tmp_dir/mod'.uniqid().'.wav';
		if(!$normalize){
			shell_exec("sox -m -v 1 '{$this->file}' -v 1 '$input' '$out'");			
		} else {
			shell_exec("sox -m '{$this->file}' '$input' '$out'");
		}
		copy($out, $this->file);
		return $this;
	}

	function unsilence($treshold='2.0'){
		return $this->mod("silence -l 1 0.1 5% -1 $treshold 0%");
	}
	
	function chop($num_slices){
	    $repeat = $num_slices - 1;
	    $trim = $this->len() / $num_slices;
	    return $this->mod("trim 0 $trim repeat $repeat");
    }

    function fade($from_val, $to_val, $attr='gain'){

        if($attr === 'gain'){
            $out = __DIR__.'/tmp_dir/fade'.uniqid().'.wav';
            $script = __DIR__."/fade.py";
            shell_exec($cmd = "python3 $script $this->file $from_val $to_val $out");
            $this->file = $out;
            return $this;
        }

        $steps = range($from_val, $to_val);
        $smp_size = $this->len() / count($steps);

        /** @var self $stream */
        $stream = null;

        foreach($steps as $i => $value){

            $offset = $i * $smp_size;
            $out = __DIR__.'/tmp_dir/fade'.uniqid().'.wav';
            shell_exec("sox '$this->file' '$out' trim $offset $smp_size $attr $value");
            if(is_null($stream)){
                $stream = new self($out);
            } else {
                $stream->add($out);
            }

        }

        shell_exec("mv '{$stream->file}' '{$this->file}'");

        return $this;
        
    }
    
    function sway(array $values, $steps=null){
        if($steps){
            $tmp = [];
            $steps++;
            while(true){
                foreach($values as $v){
                    $tmp[] = $v;
                    if(count($tmp)==$steps){
                        break 2;
                    }
                }
            }
            $values = $tmp;
        }
        $stream = null;
        foreach($this->split(count($values)-1) as $i => $part){
            $x = $values[$i];
            $y = $values[$i+1];
            $part->fade($x, $y);
            if($stream){
                $stream->add($part);
            } else {
                $stream = $part;
            }
        }

        shell_exec("mv '{$stream->file}' '{$this->file}'");
        return $this;

    }

    function each($smp_size, $callback){
        $offset = 0;
        $len = $this->len();
        /** @var self $stream */
        $stream = null;
        while($offset < $len){
            if($offset+$smp_size > $len){
                $smp_size = $len - $offset;
            }
            $smp = $this->copy($offset, $smp_size);
            $return = $callback($smp);
            if($return instanceof self){
                $smp = $return;
            }
            if(!$stream){
                $stream = $smp;
            } else {
                $stream->add($smp);
            }
            $offset += $smp_size;
        }
        shell_exec("mv '{$stream->file}' '{$this->file}'");
        return $this;
    }
    
    function split($num_pieces){
        $offset = 0;
        $smp_size = $this->len()/$num_pieces;
        $parts = [];
        for($i=1;$i<=$num_pieces;$i++){
            $parts[] = $this->copy($offset, $smp_size);
            $offset += $smp_size;
        }
        return $parts;
    }

    function part($offset, $length=null){
        require_once(__DIR__.'/AudPart.php');

        list($offset, $length) = $this->range($offset,$length);

        $copy = $this->copy($offset,$length);
        $copy->auto_gc = false;
        $ref = new AudPart($copy->file, true);
        $ref->parent = $this;
        $ref->offset = $offset;
        $ref->length = $length;
        return $ref;
    }
    
    function maxgain(){

        $tmp = __DIR__."/tmp_dir/maxgain".uniqid().".wav";

        for($i=1;$i<=100;$i++){

            $o = null;
                        
            exec("sox '$this->file' '$tmp' gain $i 2>&1", $o);   
            
            if($o && strstr(implode($o), 'gain clipped')){
                break;
            }
            
        }
        $i--;
        
        if($i>0){        
            $this->mod('gain '.$i);
        }
        
        return $i;

    }

    
	function save($as){
        if(substr($as,-4)=='.wav'){
            copy($this->file, $as);
        } else {
            shell_exec("sox '$this->file' -C 320 '$as'");
        }
		return $this;
	}

	function x($times){
		if($times<2){
			return $this;
		}
		return $this->mod('repeat '.($times-1));
	}

	function resize($newlen){
	    if(preg_match("/\.(wav|mp3)$/",$newlen)){
            $newlen = new self($newlen);
	    }
	    if($newlen instanceof self){
            $newlen = $newlen->len();
	    }
	    return $this->mod('speed '.($this->len()/$newlen));
    }

    function to120(){

        $len = $this->len();

        $targets[] = 1;
        $value = 1;

        while($value <= $len){
            $value *= 2;
            $targets[] = $value;
        }

        if(in_array($len,$targets)){
            return $this;
        }

        $closest = null;
        $lastdiff = 999;

        foreach($targets as $time){
            $diff = abs($time-$len);
            if($diff < $lastdiff){
                $lastdiff = $diff;
                $closest = $time;
            }
            if($diff == $lastdiff && rand(0,1)){
                $closest = $time;
            }
        }

        $this->resize($closest);

        return $this;
    }

    function bpm(){
        return 120 * (16/$this->len());
    }

    function toTempo($bpm){
        $this->resize((16 * 120) / $bpm);
        return $this;
    }
    
    function pick($len, $apply2self=false){
        $offset = rand(0, (($this->len() / $len) - 1)) * $len;
        $copy = $this->copy($offset,$len);
        if(!$apply2self){
            return $copy;
        }
        copy($copy->file, $this->file);
        return $this;
    }

	function play(){
		shell_exec("play '{$this->file}'");
	}

    function iswav(){
        $encoding = shell_exec("sox --info '$this->file' | grep \"Sample Encoding\"");
        if(stristr($encoding,'PCM')){
            return true;
        }
        return false;
    }

    function mp3(){
        $f = '/tmp/'.uniqid().'.mp3';
        shell_exec("sox '$this->file' -C 320 '$f'");
        return new self($f, true);
    }

    function __call($method,$args){
        $args = $args ? implode(' ', $args) : '';
        return $this->mod("$method ".$args);
    }
	function __destruct(){
	    static $auto_gc_done = false;
		if(!$auto_gc_done && $this->auto_gc){
            foreach(glob(__DIR__."/tmp_dir/*.wav") as $f){
                if(time()-filemtime($f) >= $this->auto_gc){
                    unlink($f);
                }
            }
            $auto_gc_done = true;
		}
	}

}


