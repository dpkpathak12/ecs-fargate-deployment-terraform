# variables.tf

variable "aws_region" {
  description = "The AWS region things are created in"
  default     = "us-east-1"
}

variable "ecs_task_execution_role_name" {
  description = "ECS task execution role name"
  default = "myEcsTaskExecutionRole"
}

variable "az_count" {
  description = "Number of AZs to cover in a given region"
  default     = "2"
}

variable "app_image" {
  description = "Docker image to run in the ECS cluster"
  default     = "snipe/snipe-it:v5.1.7"
}

variable "app_port" {
  description = "Port exposed by the docker image to redirect traffic to"
  default     = 3000
}

variable "app_count" {
  description = "Number of docker containers to run"
  default     = 3
}

variable "health_check_path" {
  default = "/"
}

variable "fargate_cpu" {
  description = "Fargate instance CPU units to provision"
  default     = "512"
}

variable "fargate_memory" {
  description = "Fargate instance memory to provision (in MiB)"
  default     = "1024"
}

#########RDS###############

variable "project_name" {
  default = "rds-mysql-public"
}

variable "mysql_identifier" {
  default = "snipe"
}

variable "mysql_username" {
  description = "Database administrator username"
  type        = string
  sensitive   = true
  default     = "admin"

}

variable "mysql_password" {
  description = "Database administrator password"
  type        = string
  sensitive   = true
  default     = "test123456789"
}

variable "my_env_variables"{

  # Mysql Parameters
default = [
        {
          "name": "PORT",
          "value": "80"
        },
        {
          "name": "MYSQL_PORT_3306_TCP_ADDR",
          "value": ""
        },
        {
          "name":  "MYSQL_DATABASE",
          "value": "snipe_it"
        },
        {
          "name":  "MYSQL_USER",
          "value": "admin"
        },
        {
          "name":  "MYSQL_PASSWORD"
          "value": ""
        }
      ]
}