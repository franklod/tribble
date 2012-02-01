JSDIR="/home/pedro/work/tribble/assets/js"

echo "1. Merging JS files into assets/js/tmp.css"

# create the tmp.css file
touch $JSDIR/tmp.js

 # Combine all the javascript to a single temporary file
cat $JSDIR/jquery.1.4.1-min.js \
$JSDIR/jquery.tagsinput.min.js \
$JSDIR/tribble.js > $JSDIR/tmp.js

echo "2. Minifying tmp.js to assets/js/tribble-min.js"

yui-compressor -v --nomunge -o $JSDIR/tribble.min.js $JSDIR/tmp.js

echo ""

echo "3. Deleting tmp.js"

rm $JSDIR/tmp.js