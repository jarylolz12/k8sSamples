# shifl-front-end

## Project setup
```
yarn install
```

### Compiles and hot-reloads for development
```
yarn serve
```

### Compiles and minifies for production
```
yarn build
```

### Lints and fixes files
```
yarn lint
```


### Connecting to PO Websockets
```
configure app environment, refer to PO microservice
VUE_APP_PUSHER_HOST=
VUE_APP_PUSHER_ENC=
VUE_APP_PUSHER_PORT=
VUE_APP_PUSHER_KEY=
VUE_APP_PUSHER_CLUSTER=
VUE_APP_PO_NOTIFICATION_CHANNEL=
```

### Connecting to Accounting Module
```
VUE_APP_ACCOUNTING_URL= <url for the accounting backend>
VUE_APP_ACCOUNTING_SERVICE_ORIGIN=central_system
VUE_APP_ACCOUNTING_SERVICE_KEY=<access key for backend>
VUE_APP_ACCOUNTING_ENABLED=true
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).

### Using the Container builds(Docker)
- Before prodceeding, install docker CLI or UI according to your operating system. and make sure you can run docker globally.
- Using terminal do the following:

1. go to your project folder/directory
2. supply the values and Export/set the following variables,
    - Windows:

    ```
    set NODE_VERSION=
    set IMAGE_NAME=shifl-frontend-test
    set VUE_APP_BASE_URL=
    set VUE_APP_PO_URL=
    set VUE_APP_ACCOUNTING_URL=
    set VUE_APP_ACCOUNTING_SERVICE_ORIGIN=
    set VUE_APP_ACCOUNTING_SERVICE_KEY=
    set VUE_APP_TRUCKING_URL=
    set VUE_APP_PUSHER_HOST=
    set VUE_APP_PUSHER_ENC=
    set VUE_APP_PUSHER_PORT=
    set VUE_APP_PUSHER_KEY=
    set VUE_APP_PUSHER_CLUSTER=
    set VUE_APP_PO_NOTIFICATION_CHANNEL= .
    ```
    - Unix(Linux/Mac):

    ```
    export NODE_VERSION=16.18.1
    export IMAGE_NAME=shifl-frontend-test
    export VUE_APP_BASE_URL=https://staging.shifl.com/api
    export VUE_APP_PO_URL=https://postaging.shifl.com/api
    export VUE_APP_ACCOUNTING_URL=https://accountingms.shifl.capital/api
    export VUE_APP_ACCOUNTING_SERVICE_ORIGIN=central_system
    export VUE_APP_ACCOUNTING_SERVICE_KEY=9W7892R4yXFxjwKnwxgAIArth2E1JC4ySPdrMQE3
    export VUE_APP_TRUCKING_URL=https://truckingmstest.shifl.com/apidocker
    export VUE_APP_PUSHER_HOST=postaging.shifl.com
    export VUE_APP_PUSHER_ENC=true
    export VUE_APP_PUSHER_PORT=6001
    export VUE_APP_PUSHER_KEY=91628382b01c1141860d
    export VUE_APP_PUSHER_CLUSTER=ap1
    export VUE_APP_PO_NOTIFICATION_CHANNEL=po.broadcast.notification.users.
    ```

3. run the recipe:
```
make build
```

4. after building the image, set the PORT variable run it in the local machine
```
export PORT=<DESIRED_PORT>
make run
```

5. go to your browser and copy/paste this in the URL section
```
http://localhost:<PORT>/login