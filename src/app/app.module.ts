import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule,ReactiveFormsModule } from '@angular/forms'; // <-- NgModel lives here
import { MatNativeDateModule } from '@angular/material';
import {MatDatepickerModule} from '@angular/material';
import {MatFormFieldModule} from '@angular/material';
import {HttpClientModule} from '@angular/common/http';
import { environment } from '../environments/environment';
// Import your library
import { NgxStripeModule } from 'ngx-stripe';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { ToolbarComponent } from './toolbar/toolbar.component';
import { AppRoutingModule } from './app-routing.module';
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
import { ProfileComponent } from './profile/profile.component';
import { CarwashComponent } from './carwash/carwash.component';
import { BlogListComponent } from './blog-list/blog-list.component';
import { BlogComponent } from './blog/blog.component';
import { ServiceListComponent } from './service-list/service-list.component';
import { BtDashboardComponent } from './bt-dashboard/bt-dashboard.component';
import { BtTransactionsComponent } from './bt-transactions/bt-transactions.component';
import { BtAccountComponent } from './bt-account/bt-account.component';
import { BtAccountsComponent } from './bt-accounts/bt-accounts.component';


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
    ProfileComponent,
    CarwashComponent,
    BlogListComponent,
    BlogComponent,
    ServiceListComponent,
    BtDashboardComponent,
    BtTransactionsComponent,
    BtAccountComponent,
    BtAccountsComponent
  ],
  imports: [
    AppRoutingModule,
    BrowserModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    MatNativeDateModule,
    MatDatepickerModule,
    MatFormFieldModule,
    NgxStripeModule.forRoot(environment.stripePublicKey)
  ],
  exports: [
    MatNativeDateModule,
    MatDatepickerModule,
    MatFormFieldModule
  ],
  providers: [ApiDataService, AuthGuardService, AuthService],
  bootstrap: [AppComponent]
})
export class AppModule { }
