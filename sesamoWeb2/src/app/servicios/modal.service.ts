import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ModalService {
  private closeButtonClickSource = new Subject<void>();
  closeButtonClick$ = this.closeButtonClickSource.asObservable();

  cerrarModal() {
    this.closeButtonClickSource.next();
  }
}
