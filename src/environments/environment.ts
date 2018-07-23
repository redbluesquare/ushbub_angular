// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: false,
  categoriesUrl: 'http://localhost:8888/ushbub/api/categories',
  messagesUrl: 'http://localhost:8888/ushbub/api/messages',
  productsUrl: 'http://localhost:8888/ushbub/api/products',
  profilesUrl:"http://localhost:8888/ushbub/api/profiles",
  sportscompUrl:"http://localhost:8888/ushbub/api/sports-comp",
  vendorsUrl: "http://localhost:8888/ushbub/api/vendors"
};
