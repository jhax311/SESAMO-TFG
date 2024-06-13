import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NotasEnfermeriaComponent } from './notas-enfermeria.component';

describe('NotasEnfermeriaComponent', () => {
  let component: NotasEnfermeriaComponent;
  let fixture: ComponentFixture<NotasEnfermeriaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NotasEnfermeriaComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(NotasEnfermeriaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
