import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { HttpClientModule } from '@angular/common/http/src/module';
import { Product } from '../product';
import { User } from '../user';

@Component({
  selector: 'app-collections',
  templateUrl: './collections.component.html',
  styleUrls: ['./collections.component.css']
})
export class CollectionsComponent implements OnInit {

  products: Product[];

  constructor(private apiDataService: ApiDataService) { }
  myuser: User[];

  getProducts(): void {
    this.apiDataService.getProducts()
    .subscribe(products => this.products = products);
  }

  ngOnInit(): void {
    this.getProducts();
  }



}
