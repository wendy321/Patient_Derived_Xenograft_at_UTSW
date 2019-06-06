# build a docker image from dockerfile with proxy
sudo docker build --network=host --build-arg http_proxy=*** --build-arg https_proxy=*** --build-arg proxy_user=*** --build-arg proxy_pass=*** --build-arg remoter_path=/home/program/ --build-arg remoter_host=${host_ip} --build-arg remoter_usr=${remoter_usr} --build-arg remoter_passwd=${remoter_passwd} --build-arg remoter_db=${remoter_db} --build-arg pdx_db=${pdx_db} -t remote_python_pdx.  
ps. If assign --network=host, then host_ip = 127.0.0.1
ps. If NOT assign --network=host, then host_ip = docker_host_ip (usually docker_host_ip = 172.17.0.1)
sudo docker build --network=host --build-arg http_proxy=http://proxy.swmed.edu:3128 --build-arg https_proxy=http://proxy.swmed.edu:3128 --build-arg remoter_path=/home/program/ --build-arg remoter_host=127.0.0.1 --build-arg remoter_usr=${remoter_usr} --build-arg remoter_passwd=${remoter_passwd} --build-arg remoter_db=${remoter_db} --build-arg pdx_db=PDX -t remote_python_pdx_3 .

# Inspect build history
sudo docker history remote_python_pdx_3

# list docker images
sudo docker images

# run a script directly in docker container
sudo docker run --network=host -p 5000:5000 remote_python_pdx:latest python ./program/pdx/runsamplebatchupload.py

# run an interactive bash in a docker container (w/o volume mount), if you copied program folder into the container (not save space in container)
sudo docker run --network=host -p 5000:5000 -it remote_python_pdx:latest /bin/bash 

# run an interactive bash in a docker container (w/i volume mount) , if you didn't copy program folder into the container (save space in container)
sudo docker run -p 5000:5000 -it remote_python_pdx:latest /bin/bash -v /home/wendy/public_html/pdx-v2/program_docker:/mnt

# access an already running container from outside of the container
sudo docker exec -it <container names> /bin/bash

# execute command in an already running container from outside of the container
sudo docker exec -it <container names> ls /home/lib_program

# list all docker containers including stopped containers
sudo docker container ls -a

# list running docker containers
sudo docker container ls

# stop a container
sudo docker stop [containter-id]

# start a container
sudo docker start [containter-id]

# remove a container
sudo docker rm [containter-id]

# list images
sudo docker images

# remove a image
sudo docker image rm [image-id]


