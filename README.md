* Traefik: http://traefik.tocer.localhost
* API: http://api.tocer.localhost

## Run project

### Add your API key from: https://app.freecurrencyapi.com/dashboard

```shell
echo "FREE_CURRENCY_API_KEY=your-secret-key-value" >> ./api/.env.local
```

### Init project

```shell
make init
```

### Go to inside of api container and start watch currency pair

```shell
# Enter inside the api container
make php

# Enable watching of the exchange rate
bin/console app:currency-pair:observe USD EUR --observe=1
bin/console app:currency-pair:observe USD RUB --observe=1
bin/console app:currency-pair:observe EUR RUB --observe=1
```

### Go to API dashboard

* http://api.tocer.localhost/doc

### Try to use

```shell
# CURL example
curl -X 'GET' 'http://api.tocer.localhost/exchange-rate/USD/EUR' -H 'accept: application/json'
```
