import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { HttpClientModule } from '@angular/common/http/src/module';
import { Product } from '../product';

@Component({
  selector: 'app-shop',
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.css']
})
export class ShopComponent implements OnInit {
  products: Product[];

  constructor(private apiDataService: ApiDataService) { }

  getProducts(): void {
    this.apiDataService.getProducts()
    .subscribe(products => this.products = products);
  }

  ngOnInit() {
    this.getProducts();
  }

}
