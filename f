[[ -z $1 ]] && { echo "missing arg"; exit; }

query='*'
exclude='.swp$'

for word in "$@"; do
    if [[ $word == -* ]]; then
        exclude="$exclude|"$(echo $word | tr -d '-')
    else
        query=$query$word*
    fi
done;

find $PWD -type f -ipath $query | grep -viP $exclude
