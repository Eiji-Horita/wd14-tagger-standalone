#!/bin/bash

if [ -f venv/bin/activate ]; then
    source venv/bin/activate
    python3 run.py --filter 1 --dir $1 --exclude-tag 'censored','mosaic censoring','large breasts','huge breasts','cover page','dvd cover','speech bubble','spoken heart','heart','monochrome','greyscale','blank speech bubble','bar censor'
    cat `ls $1/*.txt` > prompts.txt
    php ./mk_scns.php nmssis < prompts.txt > scnes.php
    rm prompts.txt
fi

