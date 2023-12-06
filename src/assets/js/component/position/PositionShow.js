import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ChipField, ReferenceArrayField, ReferenceField, SingleFieldList, TextField} from "react-admin";
import {Table, TableBody, TableCell, TableHead, TableRow} from "@mui/material";

export const PositionShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source={"name"}/>
        <Table>
            <TableHead>
                <TableRow>
                    <TableCell>Faction</TableCell>
                    <TableCell>Qté</TableCell>
                    <TableCell>M</TableCell>
                    <TableCell>F</TableCell>
                    <TableCell>AG</TableCell>
                    <TableCell>CP</TableCell>
                    <TableCell>AR</TableCell>
                    <TableCell>Skills</TableCell>
                    <TableCell>Cost</TableCell>
                </TableRow>
            </TableHead>
            <TableBody>
                <TableRow>
                    <TableCell>
                        <ReferenceField reference="factions" source="faction" link="show">
                            <TextField source="name"/>
                        </ReferenceField>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"min"}/>-<FieldGuesser source={"max"}/>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"m"}/>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"f"}/>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"ag"}/>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"cp"}/>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"ar"}/>
                    </TableCell>
                    <TableCell>
                        <ReferenceArrayField label="Skills" reference="skills" source="skills">
                            <SingleFieldList linkType="show">
                                <ChipField source="name"/>
                            </SingleFieldList>
                        </ReferenceArrayField>
                    </TableCell>
                    <TableCell>
                        <FieldGuesser source={"cost"}/>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </ShowGuesser>
);