let echo = require('laravel-echo-server');

echo.run({
	"authHost": "https://localhost",
	"authEndpoint": "/broadcasting/auth",
	"clients": [],
	"database": "redis",
	"databaseConfig": {
		"redis": {},
		"sqlite": {
			"databasePath": "/database/laravel-echo-server.sqlite"
		}
	},
	"devMode": true,
	"host": null,
	"port": "6001",
	"protocol": "https",
	"socketio": {},
	"secureOptions": 67108864,
	"sslCertPath": "/etc/letsencrypt/live/project.cross-contract.ru/fullchain.pem",
	"sslKeyPath": "/etc/letsencrypt/live/project.cross-contract.ru/privkey.pem",
	"sslCertChainPath": "",
	"sslPassphrase": "",
	"subscribers": {
		"http": true,
		"redis": true
	},
	"apiOriginAllow": {
		"allowCors": false,
		"allowOrigin": "",
		"allowMethods": "",
		"allowHeaders": ""
	}
});

