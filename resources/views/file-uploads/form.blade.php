<div class="form-group col-md-4">
    <label for="uploadfile1" class="control-label">{{ 'File 1' }}</label>
    <input  type="file" accept="application/pdf" name="uploadfile1" id="uploadfile1"> 
</div>
<div class="form-group col-md-4">
    <label for="uploadfile1" class="control-label">{{ 'File 2' }}</label>
    <input  type="file" accept="application/pdf" name="uploadfile2" id="uploadfile2"> 
</div>
<div class="form-group col-md-4">
    <label for="uploadfile1" class="control-label">{{ 'File 3' }}</label>
    <input  type="file" accept="application/pdf" name="uploadfile3" id="uploadfile3"> 
</div>
<div class="form-group col-md-4">
    <label for="uploadfile1" class="control-label">{{ 'File 4' }}</label>
    <input  type="file" accept="application/pdf" name="uploadfile4" id="uploadfile4"> 
</div>
<div class="form-group col-md-4">
    <label for="uploadfile1" class="control-label">{{ 'File 5' }}</label>
    <input  type="file" accept="application/pdf" name="uploadfile5" id="uploadfile5"> 
</div>

<div class="form-group col-md-12">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Upload' }}">
</div>
