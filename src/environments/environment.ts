// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: false,
  accountsUrl: 'http://localhost:8888/ushbub/api/accounts',
  categoriesUrl: 'http://localhost:8888/ushbub/api/categories',
  currenciesUrl: 'http://localhost:8888/ushbub/api/currencies',
  messagesUrl: 'http://localhost:8888/ushbub/api/messages',
  productsUrl: 'http://localhost:8888/ushbub/api/products',
  profilesUrl:"http://localhost:8888/ushbub/api/profiles",
  sportscompUrl:"http://localhost:8888/ushbub/api/sports-comp",
  targetsUrl:"http://localhost:8888/ushbub/api/targets",
  transactionsUrl:"http://localhost:8888/ushbub/api/transactions",
  vendorsUrl: "http://localhost:8888/ushbub/api/vendors",
  vendorproductsUrl: "http://localhost:8888/ushbub/api/vendorproducts",
  vehiclesUrl: "http://localhost:8888/ushbub/api/vehicles",
  vendorservicesUrl: "http://localhost:8888/ushbub/api/vendorservices",
  stripePublicKey:'pk_test_M4uEmEa8yJulBaiYVUhZRobV'
};
