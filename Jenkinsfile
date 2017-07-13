pipeline {
    agent none
    stages {
        stage('Setup') {
            steps {
                sh "pwd"
                sh "whoami"
                sh "echo $WORKSPACE"
                sh "General SCM"
                sh "git clone -b blue-ocean-1 https://git@github.com/puneet0191/redshop.git"
                sh "ls -la"
                sh "Xvfb :99 &"
                sh "export DISPLAY=:99"
                sh "sleep 3"
                sh "composer update"
                sh "mv tests/acceptance.suite.dist.jenkins.yml tests/acceptance.suite.yml"
            }
        }
        stage('Browser Tests setup') {
            steps {
                sh "sudo chown -R www-data:www-data tests/joomla-cms3"
                sh "cd /var/www/html"
                sh "ln -sf $WORKSPACE/tests/joomla-cms3 ./tests/"
                sh "cd $WORKSPACE"
                sh "git submodule update --init --recursive"
                sh "composer install --working-dir ./libraries/redshop --ansi"
                sh "npm install"
                sh "mv gulp-config.sample.jenkins.json gulp-config.json"
                sh "gulp release --skip-version"
                sh "vendor/bin/robo kill:selenium"
            }
        }
        stage('Browser Tests setup') {
            steps {
                sh "vendor/bin/robo run:tests-jenkins"
            }
        }
    }
    post {
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
