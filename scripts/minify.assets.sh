CSSDIR="/home/pedro/work/tribble/assets/css"
JSDIR="/home/pedro/work/tribble/assets/js"

echo "1. Merging CSS files into assets/css/tmp/tmp.css"

# create the tmp.css file
touch $CSSDIR/tmp.css

 # Combine all the javascript to a single temporary file
cat $CSSDIR/grid.css \
$CSSDIR/typo.css \
$CSSDIR/forms.css \
$CSSDIR/navigation.css \
$CSSDIR/alerts.css \
$CSSDIR/tribble.css \
$CSSDIR/tagsinput.css \
$CSSDIR/screens.css > $CSSDIR/tmp.css

echo "2. Minifying tmp.css to assets/css/tribble-min.css"

yui-compressor -v -o $CSSDIR/tribble.min.css $CSSDIR/tmp.css

echo ""

echo "3. Deleting tmp.css"

rm $CSSDIR/tmp.css

echo "1. Merging JS files into assets/js/tmp.css"

# create the tmp.css file
touch $JSDIR/tmp.js

 # Combine all the javascript to a single temporary file
cat $JSDIR/jquery.1.4.1-min.js \
$JSDIR/jquery.tagsinput.min.js \
$JSDIR/tribble.js > $JSDIR/tmp.js

echo "2. Minifying tmp.js to assets/js/tribble-min.js"

yui-compressor -v --nomunge --disable-optimizations --preserve-semi -o $JSDIR/tribble.min.js $JSDIR/tmp.js

echo ""

echo "3. Deleting tmp.js"

rm $JSDIR/tmp.js