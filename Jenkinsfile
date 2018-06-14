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
	stage('Automated Tests - Batch 1/3') {
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
			stage('Compare') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Compare'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(3) {
						sh "build/system-tests.sh tests/acceptance/integration/Compare_Products"
					}
				}
			}
			stage('One_Steps_Checkout') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'One_Steps_Checkout'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(3) {
						sh "build/system-tests.sh tests/acceptance/integration/One_Steps_Checkout"
					}
				}
			}
			stage('Product_Attribute') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Product_Attribute'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/integration/Product_Attribute"
					}
				}
			}
			stage('Discounts') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Discounts'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'plugins'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/integration/Discounts"
					}
				}
			}
			stage('Products') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Products'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/integration/Products"
					}
				}
			}
			stage('Quotations') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Quotations'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/integration/Quotations"
					}
				}
			}
			stage('Shopper_Groups') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Shopper_Groups'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/integration/Shopper_Groups"
					}
				}
			}
			stage('Stockroom') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Stockroom'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'plugins'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/integration/Stockroom"
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
	stage('Automated Tests - Batch 2/3') {
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
					retry(2) {
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
					retry(2) {
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
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Customizations"
					}
				}
			}
			stage('Discounts') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Discounts'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Discount_Groups/Discounts"
					}
				}
			}
			stage('Rewards') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Rewards'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Discount_Groups/Rewards"
					}
				}
			}
			stage('Notices') {
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
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Notices"
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
	stage('Automated Tests - Batch 3/3') {
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
			stage('Orders') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Orders'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Orders"
					}
				}
			}
			stage('Products') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Products'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Products"
					}
				}
			}
			stage('Shippings') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Shippings'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Shippings"
					}
				}
			}
			stage('Stockrooms') {
				agent {
					docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
					}
				}
				steps {
					script {
						env.STAGE = 'Stockrooms'
					}
					unstash 'chromeD'
					unstash 'redshop'
					unstash 'vendor'
					unstash 'joomla-cms'
					unstash 'database-dump'
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Stockrooms"
					}
				}
			}
			stage('Users') {
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
					retry(2) {
						sh "build/system-tests.sh tests/acceptance/administrator/Users"
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
