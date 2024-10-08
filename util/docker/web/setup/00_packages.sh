#!/bin/bash
set -e
set -x

# Group up several package installations here to reduce overall build time
curl -S "https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x90908c2298e5d46c2c1b55594c1bcde2763923d8" \
  | sudo gpg --batch --yes --dearmor --output "/etc/apt/keyrings/audiowaveform.gpg"

echo "deb [signed-by=/etc/apt/keyrings/audiowaveform.gpg] https://ppa.launchpadcontent.net/chris-needham/ppa/ubuntu jammy main" >> /etc/apt/sources.list.d/audiowaveform.list
echo "deb-src [signed-by=/etc/apt/keyrings/audiowaveform.gpg] https://ppa.launchpadcontent.net/chris-needham/ppa/ubuntu jammy main" >> /etc/apt/sources.list.d/audiowaveform.list

curl -S "https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x19f81a792d451fb0c42afb35fb22bf628e6f7e24" \
  | sudo gpg --batch --yes --dearmor --output "/etc/apt/keyrings/sftpgo.gpg"

echo "deb [signed-by=/etc/apt/keyrings/sftpgo.gpg] https://ppa.launchpadcontent.net/sftpgo/sftpgo/ubuntu jammy main" >> /etc/apt/sources.list.d/sftpgo.list
echo "deb-src [signed-by=/etc/apt/keyrings/sftpgo.gpg] https://ppa.launchpadcontent.net/sftpgo/sftpgo/ubuntu jammy main" >> /etc/apt/sources.list.d/sftpgo.list

apt-get update

apt-get install -y --no-install-recommends \
  audiowaveform=1.10.1-1jammy1 \
  nginx-light openssl \
  tmpreaper \
  zstd \
  sftpgo
