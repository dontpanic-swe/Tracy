#!/bin/bash

sticky_man_file=`dirname $0`"/man.svg"
sticky_man_id="sticky_man"
# da man.svg: rendilo un'unica riga, cambia " in ', collassa spazi,              | prendi il <g> con l'id specificato
sticky_man_svg=`sed -r ':a;N;$!ba;s/\n//g;s/"/'\''/g;s/\s+/ /g' $sticky_man_file | grep -Eo "<g[^>]+id='$sticky_man_id'.*</g>" `

# genera svg (file/stdin) | Aggiungi definizione sticky man
# sostituisci <image> con <use> linkato a sticky man
cat $1 | dot -Tsvg | sed "/<\\/svg>/ i <defs>$sticky_man_svg</defs>" | \
sed -r "s|<image.*\\bx=[\\\"'](-?[0-9.]+)[\\\"'].*\\by=[\\\"'](-?[0-9.]+)[\\\"'].*/>|<use xlink:href='#$sticky_man_id' x='\\1' y='\\2'/>|g" | \
sed -r 's/<a\s+xlink:(href="[^"]+")\s+xlink:(title="[^"]+")\s*>/<a \1 \2 >/g'