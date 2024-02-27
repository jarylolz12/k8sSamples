build-env:
ifndef NODE_VERSION
	$(error NODE_VERSION is not defined)
endif
ifndef IMAGE_NAME
	$(error IMAGE_NAME is not defined)
endif
ifndef VUE_APP_BASE_URL
	$(error VUE_APP_BASE_URL is not defined)
endif
ifndef VUE_APP_PO_URL
	$(error VUE_APP_PO_URL is not defined)
endif
ifndef VUE_APP_ACCOUNTING_URL
	$(error VUE_APP_ACCOUNTING_URL is not defined)
endif
ifndef VUE_APP_ACCOUNTING_SERVICE_ORIGIN
	$(error VUE_APP_ACCOUNTING_SERVICE_ORIGIN is not defined)
endif
ifndef VUE_APP_ACCOUNTING_SERVICE_KEY
	$(error VUE_APP_ACCOUNTING_SERVICE_KEY is not defined)
endif
ifndef VUE_APP_TRUCKING_URL
	$(error VUE_APP_TRUCKING_URL is not defined)
endif
ifndef VUE_APP_PUSHER_HOST
	$(error VUE_APP_PUSHER_HOST is not defined)
endif
ifndef VUE_APP_PUSHER_ENC
	$(error VUE_APP_PUSHER_ENC is not defined)
endif
ifndef VUE_APP_PUSHER_PORT
	$(error VUE_APP_PUSHER_PORT is not defined)
endif
ifndef VUE_APP_PUSHER_KEY
	$(error VUE_APP_PUSHER_KEY is not defined)
endif
ifndef VUE_APP_PUSHER_CLUSTER
	$(error VUE_APP_PUSHER_CLUSTER is not defined)
endif
ifndef VUE_APP_PO_NOTIFICATION_CHANNEL
	$(error VUE_APP_PO_NOTIFICATION_CHANNEL is not defined)
endif

build: build-env
	docker build -t $(IMAGE_NAME):naive --build-arg NODE_VERSION=$(NODE_VERSION) \
	--build-arg VUE_APP_BASE_URL=$(VUE_APP_BASE_URL) \
	--build-arg VUE_APP_PO_URL=$(VUE_APP_PO_URL) \
	--build-arg VUE_APP_ACCOUNTING_URL=$(VUE_APP_ACCOUNTING_URL) \
	--build-arg VUE_APP_ACCOUNTING_SERVICE_ORIGIN=$(VUE_APP_ACCOUNTING_SERVICE_ORIGIN) \
	--build-arg VUE_APP_ACCOUNTING_SERVICE_KEY=$(VUE_APP_ACCOUNTING_SERVICE_KEY) \
	--build-arg VUE_APP_TRUCKING_URL=$(VUE_APP_TRUCKING_URL) \
	--build-arg VUE_APP_PUSHER_HOST=$(VUE_APP_PUSHER_HOST) \
	--build-arg VUE_APP_PUSHER_ENC=$(VUE_APP_PUSHER_ENC) \
	--build-arg VUE_APP_PUSHER_PORT=$(VUE_APP_PUSHER_PORT) \
	--build-arg VUE_APP_PUSHER_KEY=$(VUE_APP_PUSHER_KEY) \
	--build-arg VUE_APP_PUSHER_CLUSTER=$(VUE_APP_PUSHER_CLUSTER) \
	--build-arg VUE_APP_PO_NOTIFICATION_CHANNEL=$(VUE_APP_PO_NOTIFICATION_CHANNEL) .

run-env:
ifndef PORT
	$(error PORT is not defineds, please use an unused port on your local machine)
endif

run:
	docker run -i -p $(PORT):80 $(IMAGE_NAME):naive
