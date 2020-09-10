import Resource from '@/api/resource';
import request from '@/utils/request';

class PositionsResource extends Resource {
  constructor() {
    super('positions');
  }

  toggleStatus(id) {
    return request({
      url: '/positions/delete/' + id,
      method: 'post',
    });
  }
}
export { PositionsResource as default };

