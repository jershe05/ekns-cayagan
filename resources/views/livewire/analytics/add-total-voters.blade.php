<div>
    <div div wire:ignore.self class="modal fade" id="addNumberOfVoters" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title" id="exampleModalLabel">Add/Update Number of Voters</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

                        <div class="form-row">
                            <div class="form-group col-12 pl-0">
                                <label for="inputState"><h5>Search a barangay</h5></label>
                            </div>
                            <div class="form-group col-12 pl-0">
                                <livewire:location-search event="setLocationToAddTotalVoter" />
                            </div>

                            <div class="form-group col-md-4">
                            <label for="total_voters">Number of Voters</label>
                            <input type="number" wire:model="totalVoter" name="total_voters" class="form-control" id="total_voters" placeholder="Total Number of Voters" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" wire:click="add" class="btn btn-primary" data-dismiss="modal">Save</button>
                        </div>


            </div>

          </div>
        </div>
      </div>
</div>
