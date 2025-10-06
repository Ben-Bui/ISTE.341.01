FROM mcr.microsoft.com/devcontainers/java:1-21

RUN su vscode -c "umask 0002 && . /usr/local/sdkman/bin/sdkman-init.sh && sdk install maven" 
ARG USERNAME=vscode
RUN mkdir -p /home/$USERNAME/.vscode-server/extensions \
/home/$USERNAME/.vscode-server-insiders/extensions \
&& chown -R $USERNAME \
/home/$USERNAME/.vscode-server \
/home/$USERNAME/.vscode-server-insiders
# Create .m2 directory with proper permissions before volume mount
RUN mkdir -p /home/vscode/.m2/repository && \
    chown -R vscode:vscode /home/vscode/.m2 && \
    chmod -R 755 /home/vscode/.m2