<?php

shell_exec("rm /tmp/test_w_img_iter* 2>/dev/null");

for($i=1;$i<=5;$i++){
    copy(__DIR__."/1x1.png", "/tmp/test_w_img_iter_$i.png");
}

$render = fn() => w_img_iter("/tmp/test_w_img_iter*.png");

// TEST: renders first img
$html = $render();

$data = b64_data_url("/tmp/test_w_img_iter_1.png");

assert_contains_in_order($html, [
    "/tmp/test_w_img_iter_1.png",
    "1 of 5",
    "<img",
    $data
]);

// TEST: goes to next img (2)
click_link($html, "Next");

$html = $render();

$data = b64_data_url("/tmp/test_w_img_iter_2.png");

assert_contains_in_order($html, [
    "/tmp/test_w_img_iter_2.png",
    "2 of 5",
    "<img",
    $data
]);

// TEST: goes back to first image
click_link($html, "Prev");

$html = $render();

$data = b64_data_url("/tmp/test_w_img_iter_1.png");

assert_contains_in_order($html, [
    "/tmp/test_w_img_iter_1.png",
    "1 of 5",
    "<img",
    $data
]);


// TEST: goes back to last image
click_link($html, "Prev");

$html = $render();

$data = b64_data_url("/tmp/test_w_img_iter_5.png");

assert_contains_in_order($html, [
    "/tmp/test_w_img_iter_5.png",
    "5 of 5",
    "<img",
    $data
]);

// TEST: goes back to first image
click_link($html, "Next");

$html = $render();

$data = b64_data_url($f="/tmp/test_w_img_iter_1.png");

assert_contains_in_order($html, [
    $f,
    "1 of 5",
    "<img",
    $data
]);

// TEST: remove first image
click_link($html, "Remove");
$html = $render();

assert_file_not_exists($f);
assert_contains(redirect_location(), '=0');
apply_redirect();
$html = $render();
assert_contains($html, '1 of 4');

// TEST: go to last image, remove last image (should go back to first)
click_link($html, 'Prev');
$html = $render();
click_link($html, 'Remove');
$render();
apply_redirect();
$html = $render();
assert_contains($html, '1 of 3');
assert_file_not_exists('/tmp/test_w_img_iter_5.png');

