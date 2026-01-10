<div>
    <input type="text" wire:model="name" id="">
    <small>you typed {{ $name }}</small>
    <button wire:click="save">check</button>
</div>