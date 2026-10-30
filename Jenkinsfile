pipeline {
  agent any
  environment {
    IMAGE = "laravel-app"
    TAG = "${BUILD_NUMBER}"
    KUBECONFIG = "/etc/rancher/k3s/k3s.yaml"
  }
  stages {
    stage('Checkout') { steps { checkout scm } }
    stage('Build Docker Image') { steps { sh 'docker build -t $IMAGE:$TAG .' } }
    stage('Load Image into Kubernetes') { steps { sh 'docker save $IMAGE:$TAG | sudo k3s ctr images import -' } }
    stage('Deploy to Kubernetes') {
      steps {
        sh '''
          kubectl apply -f k8s/secret.yaml -f k8s/mysql.yaml
          sed "s|IMAGE_PLACEHOLDER|$IMAGE:$TAG|" k8s/laravel.yaml | kubectl apply -f -
          kubectl rollout status deployment/laravel --timeout=240s
        '''
      }
    }
    stage('Verify') {
      steps {
        sh '''
          kubectl get pods,svc
          sleep 5
          curl -s http://localhost:30080/dbcheck
        '''
      }
    }
  }
}
