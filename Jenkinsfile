#!groovy

pipeline {
    agent any

    stages {
        stage('Setup') {
            steps {
                sh '''
                    pwd && \
                    whoami && \
                    echo $WORKSPACE && \
                    ls -la && \
                    (Xvfb :99 &) && \
                    export DISPLAY=:99 && \
                    sleep 3 && \
                    composer update && \
                    export DISPLAY=:99 && \
                    mv tests/acceptance.suite.dist.jenkins.yml tests/acceptance.suite.yml
                '''
            }
        }
        stage('Browser Tests setup') {
            steps {
                sh '''
                    sudo chown -R www-data:www-data tests/joomla-cms3 && \
                    cd /var/www/html && \
                    ln -sf $WORKSPACE/tests/joomla-cms3 ./tests/ && \
                    cd $WORKSPACE && \
                    git submodule update --init --recursive && \
                    composer install --working-dir ./libraries/redshop --ansi && \
                    npm install && \
                    mv gulp-config.sample.jenkins.json gulp-config.json && \
                    gulp release --skip-version && \
                    vendor/bin/robo kill:selenium
                '''
            }
        }
        stage('Browser Tests run') {
            steps {
                sh "vendor/bin/robo run:tests-jenkins"
            }
            post
            {
                always {
                    sh "export CLOUD_NAME=redcomponent"
                    sh "export API_KEY=365447364384436"
                    sh "export API_SECRET=Q94UM5kjZkZIrau8MIL93m0dN6U"
                    sh "export GITHUB_TOKEN=4d92f9e8be0eddc0e54445ff45bf1ca5a846b609"
                    sh "export ORGANIZATION=redCOMPONENT-COM"
                    sh "export REPO=redSHOP"
                    sh "echo $ORGANIZATION"
                    sh "vendor/bin/robo send:screenshot-from-travis-to-github $CLOUD_NAME $API_KEY $API_SECRET $GITHUB_TOKEN $ORGANIZATION $REPO $ghprbPullId"
                }
            }
        }
    }
}
