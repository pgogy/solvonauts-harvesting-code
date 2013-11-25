date
for file in harvesting/flickrscripts/*
do
	if [[ "$file" == *"harvestscript"* ]]; then
		php $file
	fi
done
for file in harvesting/tumblrscripts/*
do
	if [[ "$file" == *"harvestscript"* ]]; then
		php $file
	fi
done
for file in harvesting/rssscripts/*
do
	if [[ "$file" == *"harvestscript"* ]]; then
		echo $file  
		php $file
	fi
done
for file in harvesting/oaiscripts/*
do
	if [[ "$file" == *"harvestscript"* ]]; then
		echo $file
		php $file
	fi
done
date