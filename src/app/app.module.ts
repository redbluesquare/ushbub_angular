import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms'; // <-- NgModel lives here
import {HttpClientModule} from '@angular/common/http';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { ToolbarComponent } from './toolbar/toolbar.component';
import { AppRoutingModule } from './/app-routing.module';
import { ApiDataService } from './api-data.service';
import { CollectionsComponent } from './collections/collections.component';
import { AuthGuardService } from './auth-guard.service';
import { AuthService } from './auth.service';
import { HomeComponent } from './home/home.component';
import { ResourcesComponent } from './resources/resources.component';
import { ShopComponent } from './shop/shop.component';
import { ProductListComponent } from './product-list/product-list.component';
import { PowertoolsComponent } from './powertools/powertools.component';
import { PtAccessoriesComponent } from './pt-accessories/pt-accessories.component';
import { FishingComponent } from './fishing/fishing.component';
import { ShopListComponent } from './shop-list/shop-list.component';
import { EventListComponent } from './event-list/event-list.component';
import { ActivityListComponent } from './activity-list/activity-list.component';
import { WorldCupComponent } from './world-cup/world-cup.component';


@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    ToolbarComponent,
    CollectionsComponent,
    HomeComponent,
    ResourcesComponent,
    ShopComponent,
    ProductListComponent,
    PowertoolsComponent,
    PtAccessoriesComponent,
    FishingComponent,
    ShopListComponent,
    EventListComponent,
    ActivityListComponent,
    WorldCupComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    FormsModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [ApiDataService, AuthGuardService, AuthService],
  bootstrap: [AppComponent]
})
export class AppModule { }
