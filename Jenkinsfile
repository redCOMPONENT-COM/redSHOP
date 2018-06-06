#!/usr/bin/env bash
pipeline {
agent any
options {
	timeout(time: 2, unit: 'HOURS')
	buildDiscarder(logRotator(numToKeepStr: '4', daysToKeepStr: '10', artifactDaysToKeepStr: '15', artifactNumToKeepStr: '4'))
	disableConcurrentBuilds()
}
triggers {
	pollSCM '30 08 22 06 *'
}
stages {
	stage('Docker Setup') {
			steps {
				dockerNetworkCreate("tn-${BUILD_TAG}")
				dockerDBRun("db-${BUILD_TAG}", 'root', "tn-${BUILD_TAG}")
			}
	}
	stage ('Tests Setup') {
		environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO='redCOMPONENT-COM/redshop'
				CLOUDINARY_CLOUD_NAME='redcomponent'
				CLOUDINARY_API_KEY='365447364384436'
				CLOUDINARY_API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
				SLACK_WEBHOOK='https://hooks.slack.com/services/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'
				SLACK_CHANNEL='#redshop-notifications'
		}
		agent {
			docker {
				image 'jatitoam/docker-systemtests'
				args  "-v /var/lib/jenkins/.ssh:/root/.ssh --network tn-${BUILD_TAG} --user 0 --privileged=true"
			}
		}
		steps {
				sh "./build/test-setup.sh"
				stash includes: 'vendor/**', name: 'vendor'
				stash includes: 'joomla-cms.zip', name: 'joomla-cms'
				stash includes: 'chromedriver_linux64.zip', name: 'chromeD'
				stash includes: 'redshop.zip', name: 'redshop'
				stash includes: 'plugins.zip', name: 'plugins'
				stash includes: 'joomla-cms-database.zip', name: 'database-dump'
		}
		post {
			always {
				cleanWs()
			}
		}
	}
	stage('Automated Tests - Batch 1/1') {
		environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO='redCOMPONENT-COM/redshop'
				CLOUDINARY_CLOUD_NAME='redcomponent'
				CLOUDINARY_API_KEY='365447364384436'
				CLOUDINARY_API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
				SLACK_WEBHOOK='https://hooks.slack.com/services/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'
				SLACK_CHANNEL='#redshop-notifications'
		}
		parallel {
			stage('Communications') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Communications'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(1) {
						sh "build/system-tests.sh tests/acceptance/administrator/Communications"
					}
				}
			}
			stage('Configuration') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Configuration'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(1) {
						sh "build/system-tests.sh tests/acceptance/administrator/Configuration"
					}
				}
			}
			stage('Customizations') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Customizations'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(1) {
						sh "build/system-tests.sh tests/acceptance/administrator/Customizations"
					}
				}
			}
			stage('Discount_Groups') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Discount_Groups'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(1) {
						sh "build/system-tests.sh tests/acceptance/administrator/Discount_Groups"
					}
				}
			}
			stage('integration') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'administrator'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'plugins'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(1) {
						sh "build/system-tests.sh tests/acceptance/integration/Discounts"
					}
				}
			}
		}
		post {
			always {
				cleanWs()
				ws(pwd() + "@tmp") {
					cleanWs()
				}
			}
		}
	}
}
	post {
		always {
			dockerDBRemove("db-${BUILD_TAG}");
			dockerNetworkRemove("tn-${BUILD_TAG}");
			dockerVolumeRemove();
			cleanWs()
			ws(pwd() + "@") {
				cleanWs()
			}
			wipeWorkspaces() // Always run this last
		}
	}
}

def dockerNetworkCreate(name) {
	dockerNetworkRemove(name)
sh "docker network create ${name}"
}

def dockerNetworkRemove(name) {
	sh "docker network rm ${name} || true"
}

def dockerDBRun(hostname, password, network) {
	dockerDBRemove(hostname)
	sh "docker run --name ${hostname} --network ${network} -e MYSQL_ROOT_PASSWORD=${password} -d mysql:5.7"
}

def dockerDBRemove(hostname) {
	sh "docker stop ${hostname} || true"
	sh "docker rm ${hostname} || true"
}

def dockerVolumeRemove() {
	sh "docker volume prune -f"
}

def wipeWorkspaces()
{
    dir('/var/lib/jenkins/workspace'){
        sh 'pwd'
        sh 'find -maxdepth 1 -name $(basename ${WORKSPACE})@\\* ! -name $(basename ${WORKSPACE})@tmp'

        sh 'sudo rm -rf -- $(basename ${WORKSPACE})_*/'
        // Removes the clones & tmp folders created for parallelization
        sh 'find -maxdepth 1 -name $(basename ${WORKSPACE})@\\* ! -name $(basename ${WORKSPACE})@tmp -exec sudo rm -rf {} \\;'
    }

    // Wipes the main workspace afterwards leaving an empty dir
    deleteDir()
}
