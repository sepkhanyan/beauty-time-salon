import Resource from '@/api/resource';
import request from '@/utils/request';

class EmployeesResource extends Resource {
  constructor() {
    super('employees');
  }
  toggleStatus(id) {
    return request({
      url: '/employees/delete/' + id,
      method: 'post',
    });
  }
  imageUpload(id, data) {
    return request({
      url: 'employees/' + id + '/image-upload',
      method: 'post',
      data: data,
    });
  }

  imagesUpload(id, data) {
    return request({
      url: 'employees/' + id + '/images-upload',
      method: 'post',
      data: data,
    });
  }
}
export { EmployeesResource as default };

