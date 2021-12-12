# css-generator

First PHP project at Web@Cadémie.

Concatenate all images inside a folder in one sprite and write a style sheet ready to use.

-r, --recursive\n
Look for images into the assets_folder passed as argument and all of its subdirectories.\n
-i, --output-image=IMAGE\n
Name of the generated image. If blank, the default name is "sprite.png".\n
-s, --output-style=STYLE\n
Name of the generated stylesheet. If blank, the default name is "style.css".\n
-p, --padding=NUMBER\n
Add padding between images of NUMBER pixels.\n
-o, --override-size=SIZE\n
Force each images of the sprite to fit a size of SIZExSIZE pixels.\n
-c, --columns_number=NUMBER\n
The maximum number of elements to be generated horizontally.\n
-n, --class_name=NAME\n
Name of the generated CSS class. If blank, the default name is "sprite".\n
