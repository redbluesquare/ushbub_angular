import { Injectable } from '@angular/core';
import { Product } from './product';
import { Category } from './category';
import { Connector } from './connector';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';


@Injectable()
export class ApiDataService {

  constructor(private httpClient: HttpClient) { }
  private productsUrl = 'http://localhost:8888/ushbub/api/products';  // URL to web api
  private categoriesUrl = 'http://localhost:8888/ushbub/api/categories';  // URL to web api

  getProducts(): Observable<Product[]> {
    return this.httpClient.get<Product[]>(this.productsUrl);
  }

  getCategories(category): Observable<Category[]> {
    return this.httpClient.get<Category[]>(this.categoriesUrl+'/'+category);
  }

  getConnectors(category): Observable<Connector[]> {
    return this.httpClient.get<Connector[]>(this.productsUrl+'/connectors/'+category);
  }

}
