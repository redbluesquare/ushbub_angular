import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ShopComponent } from './shop/shop.component';
import { ResourcesComponent } from './resources/resources.component';
import { CollectionsComponent } from './collections/collections.component';
import { LoginComponent } from './login/login.component';
import { AuthGuardService } from './auth-guard.service';
import { HomeComponent } from './home/home.component';


const routes: Routes = [
  { path: '', component: HomeComponent,canActivate: [AuthGuardService] },
  { path: 'shop', component: ShopComponent,canActivate: [AuthGuardService] },
  { path: 'resources', component: ResourcesComponent,canActivate: [AuthGuardService] },
  { path: 'collections', component: CollectionsComponent,canActivate: [AuthGuardService] },
  { path: 'login', component: LoginComponent }
];
@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}


