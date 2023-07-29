import toastr from "toastr";
import "toastr/build/toastr.min.css";

function useToastr() {
    toastr.options.closeButton = true;
    toastr.options.closeMethod = "fadeOut";
    toastr.options.closeDuration = 300;
    toastr.options.preventDuplicates = true;
    toastr.options.progressBar = true;
    toastr.options.positionClass = "toast-bottom-right";
    toastr.options.preventOpenDuplicates = true;
    toastr.options.maxOpened= 1;
    toastr.options.autoDismiss= true;
    return toastr;
}
export default useToastr();
