import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ActivityListComponent } from './activity-list/activity-list.component';
import { EventListComponent } from './event-list/event-list.component';
import { ShopListComponent } from './shop-list/shop-list.component';
import { ShopComponent } from './shop/shop.component';
import { PowertoolsComponent } from './powertools/powertools.component';
import { FishingComponent } from './fishing/fishing.component';
import { PtAccessoriesComponent } from './pt-accessories/pt-accessories.component';
import { ResourcesComponent } from './resources/resources.component';
import { CollectionsComponent } from './collections/collections.component';
import { LoginComponent } from './login/login.component';
import { AuthGuardService } from './auth-guard.service';
import { HomeComponent } from './home/home.component';
import { ProductListComponent } from './product-list/product-list.component';
import { WorldCupComponent } from './world-cup/world-cup.component';


const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'shops', component: ShopListComponent },
  { path: 'events', component: EventListComponent },
  { path: 'activities', component: ActivityListComponent },
  { path: 'shop', component: ShopComponent,canActivate: [AuthGuardService] },
  { path: 'world-cup', component: WorldCupComponent },
  { path: 'resources', component: ResourcesComponent },
  { path: 'products', component: ProductListComponent },
  { path: 'powertools', component: PowertoolsComponent },
  { path: 'powertools/:alias', component: PowertoolsComponent },
  { path: 'fishing', component: FishingComponent },
  { path: 'fishing/:alias', component: FishingComponent },
  { path: 'fishing/:alias/:category', component: FishingComponent },
  { path: 'powertool-accessories', component: PtAccessoriesComponent },
  { path: 'collections', component: CollectionsComponent,canActivate: [AuthGuardService] },
  { path: 'login', component: LoginComponent }
];
@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {

}


