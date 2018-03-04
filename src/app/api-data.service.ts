import { Injectable } from '@angular/core';
import { Product } from './product';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';


@Injectable()
export class ApiDataService {

  constructor(private httpClient: HttpClient) { }
  private productsUrl = 'http://localhost:8888/ushbub/api/products';  // URL to web api

  getProducts(): Observable<Product[]> {
    return this.httpClient.get<Product[]>(this.productsUrl);
  }

}
