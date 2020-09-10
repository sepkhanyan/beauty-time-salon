import Resource from '@/api/resource';
import request from '@/utils/request';

class SalonCategoryResource extends Resource {
  constructor() {
    super('salon-categories');
  }

  sort(data) {
    return request({
      url: '/' + this.uri + '/sort',
      method: 'post',
      data: data,
    });
  }
}

export { SalonCategoryResource as default };
