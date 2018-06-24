import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ActivityListComponent } from './activity-list/activity-list.component';
import { EventListComponent } from './event-list/event-list.component';
import { ShopListComponent } from './shop-list/shop-list.component';
import { ShopComponent } from './shop/shop.component';
import { ProductListComponent } from './product-list/product-list.component';
import { ProfileComponent } from './profile/profile.component';
import { PowertoolsComponent } from './powertools/powertools.component';
import { FishingComponent } from './fishing/fishing.component';
import { PtAccessoriesComponent } from './pt-accessories/pt-accessories.component';
import { ResourcesComponent } from './resources/resources.component';
import { CollectionsComponent } from './collections/collections.component';
import { AuthGuardService } from './auth-guard.service';
import { HomeComponent } from './home/home.component';
import { LoginComponent } from './login/login.component';
import { ScoreBoardComponent } from './score-board/score-board.component';
import { WorldCupComponent } from './world-cup/world-cup.component';


const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'activities', component: ActivityListComponent },
  { path: 'collections', component: CollectionsComponent,canActivate: [AuthGuardService] },
  { path: 'events', component: EventListComponent },
  { path: 'fishing', component: FishingComponent },
  { path: 'fishing/:alias', component: FishingComponent },
  { path: 'login', component: LoginComponent },
  { path: 'products', component: ProductListComponent },
  { path: 'profile', component: ProfileComponent,canActivate: [AuthGuardService]  },
  { path: 'powertools', component: PowertoolsComponent },
  { path: 'powertools/:alias', component: PowertoolsComponent },
  { path: 'powertool-accessories', component: PtAccessoriesComponent },
  { path: 'resources', component: ResourcesComponent },
  { path: 'score-board', component: ScoreBoardComponent,canActivate: [AuthGuardService]  },
  { path: 'shop', component: ShopComponent,canActivate: [AuthGuardService] },
  { path: 'shops', component: ShopListComponent },
  { path: 'world-cup', component: WorldCupComponent }
];
@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {

}


