import request from '@/utils/request';
import Resource from '@/api/resource';

class SalonResource extends Resource {
  constructor() {
    super('salons');
  }

  imagesUpload(id, data) {
    return request({
      url: '/' + this.uri + '/' + id + '/images-upload',
      method: 'post',
      data: data,
    });
  }

  addCategory(id, data) {
    return request({
      url: '/' + this.uri + '/' + id + '/add-category',
      method: 'post',
      data: data,
    });
  }
}

export { SalonResource as default };
