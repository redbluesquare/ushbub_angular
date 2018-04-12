import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ShopComponent } from './shop/shop.component';
import { PowertoolsComponent } from './powertools/powertools.component';
import { ResourcesComponent } from './resources/resources.component';
import { CollectionsComponent } from './collections/collections.component';
import { LoginComponent } from './login/login.component';
import { AuthGuardService } from './auth-guard.service';
import { HomeComponent } from './home/home.component';
import { ProductListComponent } from './product-list/product-list.component';


const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'shop', component: ShopComponent,canActivate: [AuthGuardService] },
  { path: 'resources', component: ResourcesComponent },
  { path: 'products', component: ProductListComponent },
  { path: 'powertools', component: PowertoolsComponent },
  { path: 'collections', component: CollectionsComponent,canActivate: [AuthGuardService] },
  { path: 'login', component: LoginComponent }
];
@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}


