# 概要
ゲール-シャプレイ (Gale-Shapley) アルゴリズムのPHP実装  
安定結婚問題を解く

# 動作確認環境
- Windows 10
- PHP 7.1

# 使い方

```php
<?php

require_once 'GaleShapley.php';
use Kuredev\GaleShapley\{
	GaleShapley, Woman, Person, Match, Matches, Man
};


$kure1 = new Man ( "kure1", ["aki1","aki2","aki3","aki4"] );
$kure2 = new Man ( "kure2", ["aki3","aki2","aki1","aki4"] );
$kure3 = new Man ( "kure3", ["aki1","aki2","aki4","aki3"] );
$kure4 = new Man ( "kure4", ["aki3","aki1","aki4","aki2"] );
$aki1 = new Woman("aki1", ["kure1", "kure2", "kure3", "kure4"]);
$aki2 = new Woman("aki2", ["kure2", "kure1", "kure4", "kure3"]);
$aki3 = new Woman("aki3", ["kure2", "kure3", "kure1", "kure4"]);
$aki4 = new Woman("aki4", ["kure1", "kure4", "kure3", "kure2"]);
$kurearry = [$kure1, $kure2, $kure3, $kure4];
$akiarry = [$aki1, $aki2, $aki3, $aki4];

$galeShapley = new GaleShapley($kurearry, $akiarry);
var_dump($galeShapley->calc());
```

