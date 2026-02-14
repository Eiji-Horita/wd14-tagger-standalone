#!/bin/bash

if [ -f venv/bin/activate ]; then
    source venv/bin/activate
    python3 run.py --filter 1 --dir $1 --exclude-tag 'censored','mosaic censoring','large breasts','huge breasts','cover page','dvd cover','speech bubble','spoken heart','heart','monochrome','greyscale','blank speech bubble','bar censor','comic'
    php ./mk_scns.php $1/*.txt | tee scnes.php
    test -f prompts.txt && rm prompts.txt
fi

