<?php
$f = 'app/Http/Controllers/Api/V1/PostController.php';
$s = file_get_contents($f);
$old = "            \$data = \$request->validated([\n        'title' => 'required|string|max:255',\n        'body'  => 'required|string|max:65535',\n        ]);\n        \$data['author_id'] = 1;";
$new = "        \$data = \$request->validated();\n        \$data['author_id'] = User::firstOrFail()->id;";
if (strpos($s, $old) === false) { echo "NOT FOUND\n"; exit(1); }
$s = str_replace($old, $new, $s);
// add User import after Post import
$s = str_replace("use App\\Models\\Post;\n", "use App\\Models\\Post;\nuse App\\Models\\User;\n", $s);
file_put_contents($f, $s);
echo "done\n";
