import {
    AutocompleteInput,
    ChipField, DatagridConfigurable, InfiniteList,
    NumberField,
    ReferenceArrayField,
    ReferenceField,
    ReferenceInput,
    SingleFieldList,
    TextField, TextInput, usePermissions
} from "react-admin";

const PositionFilters = [
    <ReferenceInput name={'faction'} source={'faction'} reference={'factions'}>
        <AutocompleteInput name={'faction'} optionText={'name'} filterToQuery={(searchText) => ({name: searchText})}/>
    </ReferenceInput>,
    <TextInput name={'name'} source={'name'}/>
]

export const PositionList = (props) => {
    return <InfiniteList {...props} filters={PositionFilters} exporter={false}>
        <DatagridConfigurable rowClick="show" bulkActionButtons={false}>
            <ReferenceField reference="factions" source="faction" link="show">
                <TextField source="name"/>
            </ReferenceField>
            <TextField source={"name"}/>
            <TextField source={"quantity"}/>
            <TextField source={"m"}/>
            <TextField source={"f"}/>
            <TextField source={"ag"}/>
            <TextField source={"cp"}/>
            <TextField source={"ar"}/>
            <ReferenceArrayField reference="skills" source="skills">
                <SingleFieldList linkType="show">
                    <ChipField source="name"/>
                </SingleFieldList>
            </ReferenceArrayField>
            <NumberField source={"cost"}/>
        </DatagridConfigurable>
    </InfiniteList>

};