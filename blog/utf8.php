<?php
require_once "lib_splitword_full.php";

$start_time = microtime(true);

$str = "浅析我国旅行社运作模式前景程序员们一起努力来创建共享的软件基础工具：这是开源软件的基本要旨。任何有想法、有激情并且有能力实现这个想法的程序员都是在为我们解决一个常见问题，向世界分享其解决方案，然后其他程序员会在将来一起改进这个方案，这就是回报。
做这种共享的基础软件工具通常需要具有疯狂的大脑。我早该知道这些。在2005年，我开发了Prototype脚本库——现代web浏览器应用设计的第一代JavaScript类库。在当时浏览器创新一片死气沉沉的景象中，Prototype是一个与众不同的创意：我们能否通过扩充JavaScript的内置类型、通过增加具有新功能的类型来弥补JavaScript的固有缺陷？
这种思想很快的被接受。Ruby on Rails选用Prototype作为其JavaScript框架，很快人们就在一些知名的大公司，例如苹果，纽约时报的网站里发现了它的身影。
然而，不久之后，事情越来越清楚：Prototype的核心思想和这个世界的发展方向是不一致的。浏览器厂商对JavaScript复兴所做的努力是增加新的API，其中很多是和Prototype的实现相冲突。此时，程序员开始展现对一些小的，自我实现，模块化的脚步库的偏爱，而不是大型的框架。
仅仅短暂的几年时间，Prototype从一个最佳的开发准则变成了反模式的代表——依赖于你在听谁说，你甚至会相信它是Web上最糟糕的一个东西。可事实上，尽管架构上有缺陷，Prototype曾给众多程序员带来帮助。但是春来春去，我最终发现我需要走向新的征途。
作为个人，很难独自承担起Prototype的失败。批评性的博客文章让我感觉这是我个人价值的重创。看着朋友们都去使用其他的脚本库，我感觉我的工作都是在浪费时间。
但这是一种我们让共享软件向前进步的过程。为了跟上最新的技术，我们不仅要能去尝试新思想，还要能放弃那些已经不可用的或者有更好的思想替代的旧思想。我们必须有勇气的坦率的说出代码中的问题，去除内心的自负对犯错的恐惧。
在开源世界里我学到了——我并不是我的代码。对我的软件作品的批评并不是对我个人的攻击。我的软件的替代品的出现并不是一个敌意或分化。它只是人们永不停息的对现状改进的愿望驱动下的一个简单的更新换代的结果。
我Sam Stephenson，37signals公司的一个程序员。";

$sp = new SplitWord();
$words = $sp->SplitRMM($str);
$sp->Clear();

$words_array = explode(' ', $words);
$key_word = array();
$del_word = array('的','是');
foreach ($words_array as $key => $word) {

    if (in_array($word, $del_word)) {
        continue;
    }

    if (strlen($word)< 6) {
        continue;
    }

    echo $word . '='.strlen($word);

    if (isset($key_word[$word])) {
        $key_word[$word] ++; 
    } else {
        $key_word[$word] = 1; 
    }
}
arsort($key_word);
print_r($key_word);


$end_time = microtime(true);
echo '耗时'.round($end_time-$start_time,3).'秒';
