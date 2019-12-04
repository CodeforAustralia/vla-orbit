/**
 * This method is being migrated from the old JS.
 *
 * @export
 * @param {String} model name of the model we want to display
 * @param {Object} data Object with information of each model
 * @returns
 */
export default function buttonPermissions(model, data) {

    const role = document.querySelector('.role').id;
    const sp_id = document.querySelector('.sp_id').id;

    let can_edit = false;
    let can_delete = false;
    let can_view = false;

    switch (role) {
        case 'CLC':
        case 'VLA':
            if (model == 'service') {
                can_view = true;
            }
            break;
        case 'AdminSp':
            switch (model) {
                case 'service':
                    if (data.sp_id == sp_id) {
                        can_edit = true;
                        can_view = true;
                    } else {
                        can_view = true;
                    }
                    break;
                case 'service_provider':
                    if (data.ServiceProviderId == sp_id) {
                        can_edit = true;
                    }
                    break;
                case 'no_reply_emails_templates':
                    if (data.Section != 'All') {
                        can_edit = true;
                        can_delete = true;
                    } else if (data.UserSp == sp_id) {
                        can_edit = true;
                        can_delete = true;
                    }
                    break;
                case 'service_booking':
                    can_edit = true;
                    can_delete = true;
                    break;
                case 'question':
                    can_edit = true;
                case 'matter':
                case 'sms_template':
                default:
            }
            break;
        case 'AdminSpClc':
            switch (model) {
                case 'service':
                    if (data.sp_id == sp_id) {
                        can_edit = true;
                        can_view = true;
                    } else {
                        can_view = true;
                    }
                    break;
                case 'service_provider':
                    if (data.ServiceProviderId == sp_id) {
                        can_edit = true;
                    }
                    break;
                case 'no_reply_emails_templates':
                    if (data.Section != 'All') {
                        can_edit = true;
                        can_delete = true;
                    }
                    break;
                case 'question':
                    can_edit = true;
                case 'matter':
                case 'sms_template':
                default:
            }
            break;
        case 'Administrator':
            can_edit = true;
            can_delete = true;
            can_view = true;
            break;
        default:
            break;
    }

    return {
        can_edit,
        can_delete,
        can_view
    };
}
