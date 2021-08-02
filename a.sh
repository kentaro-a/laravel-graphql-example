i=0
max_try=60
while true 
do
	sleep 1
	i=$(expr $i + 1)
	echo $i
	if [ $max_try -lt $i ]; then
		break
	fi
done 
