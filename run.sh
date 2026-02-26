#!/bin/bash

# exit if less than 1 arguments provided
if [ "$#" -le 1 ]; then
    echo "Usage: $0 <directory> <output> [additional python args...]"
    echo "  <directory> : directory containing text files to process"
    echo "  <output>    : file where mk_scns.php output will be written"
    exit 1
fi

dir=$1
shift
output=$1
shift
rest_args=$*
if [ -f venv/bin/activate ]; then
    source venv/bin/activate
    python3 run.py --threshold 0.05 --filter 1 --dir $dir --exclude-tag 'censored','mosaic censoring','large breasts','huge breasts','cover page','dvd cover','speech bubble','spoken heart','heart','monochrome','greyscale','blank speech bubble','bar censor','comic','loli','watermark','realistic' $rest_args
    php ./mk_scns.php $dir/*.txt | tee $output
    test -f prompts.txt && rm prompts.txt
fi

