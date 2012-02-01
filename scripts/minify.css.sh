CSSDIR="/home/pedro/work/tribble/assets/css"

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