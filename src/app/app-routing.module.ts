import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ActivityListComponent } from './activity-list/activity-list.component';
import { AuthGuardService } from './auth-guard.service';
import { BlogListComponent } from './blog-list/blog-list.component';
import { BlogComponent } from './blog/blog.component';
import { BtAccountsComponent } from './bt-accounts/bt-accounts.component';
import { BtDashboardComponent } from './bt-dashboard/bt-dashboard.component';
import { BtTransactionsComponent } from './bt-transactions/bt-transactions.component';
import { CarwashComponent } from './carwash/carwash.component';
import { CollectionsComponent } from './collections/collections.component';
import { EventListComponent } from './event-list/event-list.component';
import { FishingComponent } from './fishing/fishing.component';
import { HomeComponent } from './home/home.component';
import { LoginComponent } from './login/login.component';
import { ProductListComponent } from './product-list/product-list.component';
import { ProfileComponent } from './profile/profile.component';
import { PowertoolsComponent } from './powertools/powertools.component';
import { PtAccessoriesComponent } from './pt-accessories/pt-accessories.component';
import { ResourcesComponent } from './resources/resources.component';
import { ServiceListComponent } from './service-list/service-list.component';
import { ShopListComponent } from './shop-list/shop-list.component';
import { ShopComponent } from './shop/shop.component';


const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'accounts', component: BtAccountsComponent,canActivate: [AuthGuardService] },
  { path: 'accounts/:id', component: BtAccountsComponent,canActivate: [AuthGuardService] },
  { path: 'activities', component: ActivityListComponent },
  { path: 'blogs', component: BlogListComponent },
  { path: 'blog/:alias', component: BlogComponent },
  { path: 'carwash', component: CarwashComponent },
  { path: 'collections', component: CollectionsComponent,canActivate: [AuthGuardService] },
  { path: 'dashboard', component: BtDashboardComponent,canActivate: [AuthGuardService] },
  { path: 'events', component: EventListComponent },
  { path: 'fishing', component: FishingComponent },
  { path: 'fishing/:alias', component: FishingComponent },
  { path: 'fishing/:alias/:category', component: FishingComponent },
  { path: 'login', component: LoginComponent },
  { path: 'login/:ref', component: LoginComponent },
  { path: 'products', component: ProductListComponent },
  { path: 'profile', component: ProfileComponent,canActivate: [AuthGuardService]  },
  { path: 'powertools', component: PowertoolsComponent },
  { path: 'powertools/:alias', component: PowertoolsComponent },
  { path: 'powertool-accessories', component: PtAccessoriesComponent },
  { path: 'resources', component: ResourcesComponent },
  { path: 'services', component: ServiceListComponent },
  { path: 'shop', component: ShopComponent,canActivate: [AuthGuardService] },
  { path: 'shop/:alias', component: ShopComponent,canActivate: [AuthGuardService] },
  { path: 'shops', component: ShopListComponent },
  { path: 'transactions', component: BtTransactionsComponent,canActivate: [AuthGuardService] }
];
@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {

}


