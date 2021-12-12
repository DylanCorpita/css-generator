<?php

/*

Bonus effectués : 
- image resize
- padding
- commande -h ou --help avec couleurs (+ MAGNIFIQUE ascii art)
- création automatiquement d'un dossier résultat (s'il n'existe pas) pour stocker css/png
- choix du nom de la classe CSS

Idée bonus : 
- Ajouter un dossier résultat comme on le souhaite
- Ajouter padding en px ou autre

*/

function my_scandir(&$tab, $dir_path)
{
    global $recursive;
    if(!is_dir("./$dir_path"))
    {
        echo "\e[31mThe folder does not exists \n\e[0m";
        exit();
    }
    
    $dossier = opendir($dir_path);

    while (false !== ($handle = readdir($dossier)))
    {
        if($handle == '.' || $handle == '..')
            continue;

        if(preg_match('/.png/m', $handle))
            array_push($tab, $dir_path . '/' . $handle);

        if($recursive == true)
        {
            if(is_dir($dir_path . '/' . $handle))
                my_scandir($tab, $dir_path . '/' . $handle);
        }
    }
    closedir($dossier);
}

// Get args 
function initProcess()
{
    $shortArg = "hri::s::p:o:c:n::";
    $longArg = array(
        "help",
        "recursive",
        "output-image::",
        "output-style::",
        "padding:",
        "override-size:",
        "columns_number:",
        "class_name::",
    );

    $args = getopt($shortArg, $longArg);

    return $args;
}

// Entry point
$args = initProcess();

// Mini-bonus
if(isset($args['h']) || isset($args['help']))
{
    echo "\e[35m
     ________________    _____________   ____________  ___  __________  ____ 
    / ____/ ___/ ___/   / ____/ ____/ | / / ____/ __ \/   |/_  __/ __ \/ __ \
   / /    \__ \\\__ \   / / __/ __/ /  |/ / __/ / /_/ / /| | / / / / / / /_/ /
  / /___ ___/ /__/ /  / /_/ / /___/ /|  / /___/ _, _/ ___ |/ / / /_/ / _, _/ 
  \____//____/____/   \____/_____/_/ |_/_____/_/ |_/_/  |_/_/  \____/_/ |_|\n\n\n\e[0m";  
    echo "\e[94m\nUSAGE: css_generator [OPTIONS]. . . assets_folder\n\n\e[0m";
    echo "\e[32m-h, --help\n\e[33mGet help for CSS generator usage.\n\n\e[0m";  
    echo "\e[32m-r, --recursive\n\e[33mLook for images into the assets_folder passed as arguement and all of its subdirectories.\n\n\e[0m";
    echo "\e[32m-i, --output-image=IMAGE\n\e[33mName of the generated image. If blank, the default name is « sprite.png ».\n\n\e[0m";    
    echo "\e[32m-s, --output-style=STYLE\n\e[33mName of the generated stylesheet. If blank, the default name is « style.css »\n\n\e[0m";
    echo "\e[32m-p, --padding=NUMBER\n\e[33mAdd padding between images of NUMBER pixels.\n\n\e[0m";
    echo "\e[32m-o, --override-size=SIZE\n\e[33mForce each images of the sprite to fit a size of SIZExSIZE pixels.\n\n\e[0m";
    echo "\e[32m-c, --columns_number=NUMBER\n\e[33mThe maximum number of elements to be generated horizontally.\n\n\e[0m";
    return;
}

$dir = end($argv);  

if($dir != "assets_folder")
{
    echo "\e[31mLast arg have to be ''assets_folder'' !\n\e[0m";
    return;
}

$recursive = false;
$outputImage = false;
$outputStyle = false;
$padding = false;
$overrideSize = false;
$columns_number = false;
$class_name = false;

// Project requirements
if(isset($args['r']) || isset($args['recursive']))
    $recursive = true;

if(isset($args['i']) || isset($args['output-image']))
{
    if(isset($args['i']))
    {
        if(strpos($args['i'], '.') !== false) 
        {
            echo "\e[31mThe image name can't have \".\"\n\e[0m";
            return;
        }
        else
            $outputImage = $args['i'];
    }
    else
    {
        if(strpos($args['output-image'], '.') !== false) 
        {
            echo "\e[31mThe image name can't have \".\" \n\e[0m";
            return;
        }
        else
            $outputImage = $args['output-image'];      
    } 
}
else
    $outputImage = "sprite";

if(isset($args['s']) || isset($args['output-style']))
{
    if(isset($args['s']))
        $outputStyle = $args['s'];
    else
        $outputStyle = $args['output-style']; 
}
else
    $outputStyle = "style";

// Bonus
if(isset($args['p']) || isset($args['padding']))
{
    if(isset($args['p']))
    {
        if(is_numeric($args['p']) && $args['p'] > 0)
            $padding = $args['p'];
        else
        {
            echo "\e[31mPadding can only be used with positif number !\n\e[0m";
            return;
        }   
    }
    else // padding
    {
        if(is_numeric($args['padding']) && $args['p'] > 0)
            $padding = $args['padding'];
        else
        {
            echo "\e[31mPadding can only be used with positif number !\n\e[0m";          
            return;
        }
    }
}

if(isset($args['o']) || isset($args['override-size']))
{
    if(isset($args['o']))
    {
        if($args['o'] > 0 && is_numeric($args['o']))
            $overrideSize = $args['o'];
        else
        {
            echo "\e[31mOverride Size can only be a number (only positif number) !\n\e[0m";          
            return;  
        } 
    }
    else
    {
        if($args['override-size'] > 0 && is_numeric($args['o']))
            $overrideSize = $args['override-size'];
        else
        {
            echo "\e[31mOverride Size can only be a number (only positif number) !\n\e[0m";          
            return;  
        } 
    }  
}

if(isset($args['c']) || isset($args['columns_number']))
{
    if(isset($args['c']))
        $columns_number = $args['c'];
    else
        $columns_number = $args['columns_number-size'];
}

// CSS class name
// TODO : REGEX CHECK FOR CLASS NAMEEEE
if(isset($args['n']) || isset($args['class_name']))
{
    if(isset($args['n']))
        $class_name = $args['n'];
    else
        $class_name = $args['class_name'];      
}
else
    $class_name = "sprite";

$tab = [];
my_scandir($tab, $dir);

if(count($tab) <= 0)
{
    echo "\e[31m$dir doesn't contains any png file\n\e[0m";
    return;
}

if(count($tab) <= 1)
{
    echo "\e[31mAt least two images are needed to make a merge !\n\e[0m";
    return;
}

$totalX = 0;
$totalY = 0;
$yArr = array();
$yPosArr = array();

foreach($tab as $key => $val)
{
    $imgTmp = imagecreatefrompng($val);

    if($overrideSize)
        $imgTmp = imagescale($imgTmp, $overrideSize, $overrideSize);

    imagepng($imgTmp, "./$totalX.png");

    $totalY += imagesy($imgTmp);
    if($totalX < imagesx($imgTmp))
        $totalX = imagesx($imgTmp);
    if($padding)
        $totalY += $padding;

    // On veut du padding qu'entre les éléments, pas sur le dernier Michel !
    if($padding && array_key_last($tab) == $key)
        $totalY -= $padding;

    array_push($yArr, $totalY);
}

// Créer la sprite entière
$spriteComplet = imagecreatetruecolor($totalX, $totalY);
imagesavealpha($spriteComplet, true);
imagealphablending($spriteComplet, false);
$color = imagecolorallocatealpha($spriteComplet, 255, 255, 255, 127);

imagefilledrectangle($spriteComplet, 0, 0, imagesx($spriteComplet), imagesy($spriteComplet), $color);

$posY = 0;
$posX = 0;

foreach($tab as $key => $val)
{    
    // Créer un sprite 
    $imgTmp = imagecreatefrompng($val);

    if($overrideSize)
        $imgTmp = imagescale($imgTmp, $overrideSize, $overrideSize);
    // Sprite temporaire contenant image + marge
    $imgX = imagesx($spriteComplet) - imagesx($imgTmp);

    // On veut du padding qu'entre les éléments, pas sur le dernier Michel !
    if($padding && array_key_last($tab) == $key)
        $sprite = imagecreatetruecolor(imagesx($spriteComplet), imagesy($imgTmp)+$padding);
    else
        $sprite = imagecreatetruecolor(imagesx($spriteComplet), imagesy($imgTmp)+$padding);
        
    imagesavealpha($sprite, true);
    imagealphablending($sprite, false);
    $color = imagecolorallocatealpha($sprite, 255, 255, 255, 127);

    imagefilledrectangle($sprite, 0, 0, imagesx($sprite), imagesy($sprite), $color);
 
    imagecopy($sprite, $imgTmp, 0, 0, 0, 0, imagesx($imgTmp), imagesy($imgTmp));

    // On met tout dans la sprite
    imagecopy($spriteComplet, $sprite, 0, $posY, 0, 0, imagesx($sprite), imagesy($sprite));
 
    $posY += imagesy($sprite);
    
    // On pense encore à libérer la mémoire nah mais oh !
    imagedestroy($sprite);
    imagedestroy($imgTmp);
}

if(!is_dir("./resultat")) // On vérifie que le dossier "resultat existe bien, et sinon on le créer
    mkdir("resultat");

// Enregistrer la sprite
imagepng($spriteComplet, "resultat/$outputImage.png");

// Libérer la mémoire 
imagedestroy($spriteComplet);

// Créer fichier .css
$concat = $totalY."px";
$concat2 = $totalX."px";

$test = ".$class_name
{
    background-image: url('$outputImage.png');
    background-repeat: no-repeat;
    height: $concat;
    width: $concat2;
}";

$css = fopen("./resultat/$outputStyle.css", "w");
fwrite($css, $test);

// Libérer la mémoire
fclose($css);

    echo "\e[35m
     ________________    _____________   ____________  ___  __________  ____ 
    / ____/ ___/ ___/   / ____/ ____/ | / / ____/ __ \/   |/_  __/ __ \/ __ \
   / /    \__ \\\__ \   / / __/ __/ /  |/ / __/ / /_/ / /| | / / / / / / /_/ /
  / /___ ___/ /__/ /  / /_/ / /___/ /|  / /___/ _, _/ ___ |/ / / /_/ / _, _/ 
  \____//____/____/   \____/_____/_/ |_/_____/_/ |_/_/  |_/_/  \____/_/ |_|\n\n\n\e[0m";


echo "\e[32m[CSS GENERATOR] SPRITE GENERATED !\n\e[0m";
echo "\e[33mFile: $outputImage.png\n\e[0m";
echo "\e[33mFile: $outputStyle.css\n\e[0m";