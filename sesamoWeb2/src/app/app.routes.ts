import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login.component';
import { LayoutComponent } from './pages/layout/layout.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { authGuard } from './guards/auth.guard';
import { PageNotFoundComponent } from './pages/page-not-found/page-not-found.component';
import { NavigationComponent } from './admin/navigation/navigation.component';
import { DashboardAdminComponent } from './admin/pages/dashboard-admin/dashboard-admin.component';
import { UsuariosComponent } from './admin/pages/usuarios/usuarios.component';



  export const routes: Routes = [
    {
      path: '',
      redirectTo: 'login',
      pathMatch: 'full',
    },
    {
      path: 'login',
      component: LoginComponent,
    },
    {
      path: 'admin',
      component: NavigationComponent,
      canActivate: [authGuard],
      data: { rolEsperado: '7' },
      children: [
        { path: 'dashboard', component: DashboardAdminComponent },
        { path: 'usuarios', component: UsuariosComponent },
        { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      ]
    },
    {
      path: 'user',
      component: LayoutComponent,
      canActivate: [authGuard],
      data: { rolEsperado: 'user' },
      children: [
        {
          path: 'dashboard',
          component: DashboardComponent
        },
        { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      ],
    },
    {
      path: '**',
      component: PageNotFoundComponent
    }
  ];