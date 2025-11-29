<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $navigationGroup = 'Customer Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Customers';

    // Filter hanya user dengan tipe customer
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_type', 'customer');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('uuid')
                            ->label('UUID')
                            ->default(fn () => \Illuminate\Support\Str::uuid()->toString())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(36),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_hash')
                            ->label('Password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                        Forms\Components\Hidden::make('user_type')
                            ->default('customer')
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->native(false),
                        Forms\Components\FileUpload::make('profile_image')
                            ->label('Foto Profile')
                            ->image()
                            ->directory('profile-images')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi SIM')
                    ->schema([
                        Forms\Components\TextInput::make('driver_license_number')
                            ->label('Nomor SIM')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\FileUpload::make('driver_license_image')
                            ->label('Foto SIM')
                            ->image()
                            ->directory('license-images')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Profil Customer')
                    ->relationship('customerProfile')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->label('Kota')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('province')
                            ->label('Provinsi')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Kode Pos')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('emergency_contact_name')
                            ->label('Nama Kontak Darurat')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('emergency_contact_phone')
                            ->label('Telepon Kontak Darurat')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('membership_level')
                            ->label('Level Membership')
                            ->options([
                                'regular' => 'Regular',
                                'silver' => 'Silver',
                                'gold' => 'Gold',
                            ])
                            ->default('regular'),
                        Forms\Components\TextInput::make('rating')
                            ->label('Rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.1)
                            ->default(5.0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_verified')
                            ->label('Terverifikasi')
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Forms\Components\DateTimePicker::make('last_login')
                            ->label('Login Terakhir')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name ?? 'User') . '&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telepon')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\BadgeColumn::make('customerProfile.membership_level')
                    ->label('Membership')
                    ->colors([
                        'secondary' => 'regular',
                        'info' => 'silver',
                        'warning' => 'gold',
                    ])
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : 'Regular')
                    ->default('Regular'),
                Tables\Columns\TextColumn::make('customerProfile.total_rentals')
                    ->label('Total Rental')
                    ->numeric()
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('customerProfile.total_spent')
                    ->label('Total Pengeluaran')
                    ->money('IDR')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('customerProfile.rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 4.5 => 'success',
                        $state >= 3.5 => 'warning',
                        $state >= 2.5 => 'info',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' ⭐' : '-')
                    ->default(5.0),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('last_login')
                    ->label('Last Login')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('membership_level')
                    ->label('Level Membership')
                    ->relationship('customerProfile', 'membership_level')
                    ->options([
                        'regular' => 'Regular',
                        'silver' => 'Silver',
                        'gold' => 'Gold',
                    ]),
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verified')
                    ->placeholder('Semua')
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('Semua')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
